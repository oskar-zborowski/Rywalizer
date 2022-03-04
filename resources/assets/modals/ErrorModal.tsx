import { BlackButton, OrangeButton } from '@/components/Form/Button/Button';
import Modal from '@/components/Modal/Modal';
import React, { useState } from 'react';
import styles from '@/components/Modal/Modal.scss';

export interface IErrorModalProps {
    error: string;
    isOpen: boolean;
    setIsOpen: (isOpen: boolean) => void;
}

const ErrorModal: React.FC<IErrorModalProps> =({error, isOpen, setIsOpen}) => {
    return (
        <Modal
            className={styles.error}
            title="Błąd"
            isOpen={isOpen}
            onClose={() => {
                setIsOpen(false);
            }}
            width="450px"
            footerItems={[
                <BlackButton key="2" onClick={() => setIsOpen(false)}>Zamknij</BlackButton>
            ]}
        >
            <div style={{ fontSize: '12px', color: '#a1a1a1'}}>
                {error}
            </div>
        </Modal>
    );
};

export default ErrorModal;