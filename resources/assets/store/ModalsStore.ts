import { makeAutoObservable } from 'mobx';

//TODO lepiej to rozwiązać bo to co jest niżej to jakieś XD

export class ModalsStore {

    public isLoginEnabled = false;
    public isRegisterEnabled = false;
    public isRemindPasswordEnabled = false;
    public isResetPasswordEnabled = false;
    public isPartnerModalEnabled = false;

    public constructor() {
        makeAutoObservable(this);
    }

    public setIsLoginEnabled(value: boolean) {
        this.isLoginEnabled = value;
        this.isRegisterEnabled = false;
        this.isRemindPasswordEnabled = false;
        this.isResetPasswordEnabled = false;
        this.isPartnerModalEnabled = false;
    }

    public setIsRegisterEnabled(value: boolean) {
        this.isLoginEnabled = false;
        this.isRegisterEnabled = value;
        this.isRemindPasswordEnabled = false;
        this.isResetPasswordEnabled = false;
        this.isPartnerModalEnabled = false;
    }

    public setIsRemindPasswordEnabled(value: boolean) {
        this.isLoginEnabled = false;
        this.isRegisterEnabled = false;
        this.isRemindPasswordEnabled = value;
        this.isResetPasswordEnabled = false;
        this.isPartnerModalEnabled = false;
    }

    public setIsResetPasswordEnabled(value: boolean) {
        this.isLoginEnabled = false;
        this.isRegisterEnabled = false;
        this.isRemindPasswordEnabled = false;
        this.isResetPasswordEnabled = value;
        this.isPartnerModalEnabled = false;
    }

    public setIsPartnerModalEnabled(value: boolean) {
        this.isLoginEnabled = false;
        this.isRegisterEnabled = false;
        this.isRemindPasswordEnabled = false;
        this.isResetPasswordEnabled = false;
        this.isPartnerModalEnabled = value;
    }

}

const modalsStore = new ModalsStore();
export default modalsStore;