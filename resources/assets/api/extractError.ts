import { AxiosError } from 'axios';

const extractError = (error: AxiosError) => {
    if (error.response) {
        const errorObject = error.response.data?.data;

        if (!errorObject) {
            return new Error('Wystąpił nieznany błąd');
        }

        const entries = Object.entries<string[]>(errorObject);
        let errorMsg = errorObject;
        let iters = 0;

        if (errorMsg) {
            while (Array.isArray(errorMsg) && iters < 5) {
                errorMsg = errorMsg[0];
                iters++;
            }

            if (typeof errorMsg === 'string') {
                if (errorMsg.toLowerCase().startsWith('your email address is not verified')) {
                    errorMsg = 'Twój adres e-mail nie jest zweryfikowany.';
                }

                return new Error(errorMsg);
            } else {
                return new Error('Wystąpił nieznany błąd');
            }
        } else {
            return new Error('Wystąpił nieznany błąd');
        }
    } else {
        return new Error('Wystąpił nieznany błąd');
    }
};

export default extractError;