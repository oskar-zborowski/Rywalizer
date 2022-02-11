import { makeAutoObservable } from 'mobx';

//TODO lepiej to rozwiązać bo to co jest niżej to jakieś XD

export class ModalsStore {

    public isLoginEnabled = false;
    public isRegisterEnabled = false;
    public isRemindPasswordEnabled = false;
    public isResetPasswordEnabled = false;

    public constructor() {
        makeAutoObservable(this);
    }

    public setIsLoginEnabled(value: boolean) {
        this.isLoginEnabled = value;
        this.isRegisterEnabled = false;
        this.isRemindPasswordEnabled = false;
        this.isResetPasswordEnabled = false;
    }

    public setIsRegisterEnabled(value: boolean) {
        this.isLoginEnabled = false;
        this.isRegisterEnabled = value;
        this.isRemindPasswordEnabled = false;
        this.isResetPasswordEnabled = false;
    }

    public setIsRemindPasswordEnabled(value: boolean) {
        this.isLoginEnabled = false;
        this.isRegisterEnabled = false;
        this.isRemindPasswordEnabled = value;
        this.isResetPasswordEnabled = false;
    }

    public setIsResetPasswordEnabled(value: boolean) {
        this.isLoginEnabled = false;
        this.isRegisterEnabled = false;
        this.isRemindPasswordEnabled = false;
        this.isResetPasswordEnabled = value;
    }

}

const modalsStore = new ModalsStore();
export default modalsStore;