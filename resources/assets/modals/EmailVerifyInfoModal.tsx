import { OrangeButton } from '@/components/Form/Button/Button';
import Modal from '@/components/Modal/Modal';
import React, { useState } from 'react';

export interface IEmailVerifyInfoModalProps {
    isOpen: boolean;
    setIsOpen: (isOpen: boolean) => void;
}

const EmailVerifyInfoModal: React.FC<IEmailVerifyInfoModalProps> = ({isOpen, setIsOpen}) => {
    return (
        <Modal
            title="Aktywacja konta"
            isOpen={isOpen}
            onClose={() => setIsOpen(false)}
            width="450px"
            footerItems={[
                <OrangeButton key="2" onClick={() => setIsOpen(false)}>Zamknij</OrangeButton>
            ]}
        >
            <div style={{ fontSize: '12px', color: '#a1a1a1'}}>
                Na podany adres e-mail został wysłany link aktywacyjny.
            </div>
        </Modal>
    );
};

export default EmailVerifyInfoModal;