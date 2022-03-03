import extractError from '@/api/extractError';
import Flexbox from '@/components/Flexbox/Flexbox';
import { OrangeButton } from '@/components/Form/Button/Button';
import Input from '@/components/Form/Input/Input';
import Modal from '@/components/Modal/Modal';
import modalsStore from '@/store/ModalsStore';
import userStore from '@/store/UserStore';
import { AxiosError } from 'axios';
import { observer } from 'mobx-react';
import React, { useState } from 'react';

const RemindPasswordModal: React.FC = observer(() => {
    const [email, setEmail] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState<string>('');

    const remindPassword = async () => {
        setIsLoading(true);
        
        try {
            await userStore.remindPassword(email);

            modalsStore.setIsRemindPasswordEnabled(false);
        } catch (err) {
            setError(extractError(err as AxiosError).message);
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <Modal
            title="Resetowanie hasła"
            isOpen={modalsStore.isRemindPasswordEnabled}
            onClose={() => modalsStore.setIsRemindPasswordEnabled(false)}
            isLoading={isLoading}
            width="450px"
            footerItems={[
                <OrangeButton key="2" onClick={remindPassword}>Wyślij</OrangeButton>
            ]}
        >
            <Flexbox flexDirection="column" gap="10px">
                <Input label="Adres e-mail" value={email} onChange={(v) => setEmail(v)} />
                {error && <div style={{fontWeight: 'bold', color: 'red'}}>{error}</div>}
                <div style={{ fontSize: '12px', color: '#a1a1a1'}}>
                    Na podany adres e-mail zostanie wysłany link umożliwiający zresetowania hasła.
                </div>
            </Flexbox>
        </Modal>
    );
});

export default RemindPasswordModal;