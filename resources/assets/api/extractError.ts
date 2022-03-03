import { AxiosError } from 'axios';

const extractError = (error: AxiosError) => {
    if (error.response) {
        const errorObject = error.response.data?.data;
        const entries = Object.entries<string[]>(errorObject);
        const errorMsg = entries?.[0]?.[1]?.[0];

        if (errorMsg) {
            return new Error(errorMsg);
        } else {
            return new Error('Wystąpił nieznany błąd');
        }
    }
};

export default extractError;