import Modal from '@/components/Modal/Modal';
import React, { useState } from 'react';

export interface RegisterModalProps {
    isOpen: boolean;
    onClose: () => void;
}

const RegisterModal: React.FC<RegisterModalProps> = ({ isOpen, onClose }) => {
    return (
        <Modal isOpen={isOpen} onClose={onClose}>
            <span onClick={() => onClose()}>close</span><br/><br/><br/>
            Logo Fejsa<br/>
            Logo Googla<br/><br/><br/>
            Standardowe pola do rejestracji
        </Modal>
    );
};

export default RegisterModal;