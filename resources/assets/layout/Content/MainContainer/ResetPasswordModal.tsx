import Modal from '@/components/Modal/Modal';
import React, { useState } from 'react';

export interface ResetPasswordModalProps {
    isOpen: boolean;
    onClose: () => void;
}

const ResetPasswordModal: React.FC<ResetPasswordModalProps> = ({ isOpen, onClose }) => {
    return (
        <Modal 
            title="Reset hasła"
            isOpen={isOpen}
            onClose={onClose}
        >
            <span onClick={() => onClose()}>close</span><br/><br/><br/>
            Standardowe pola do logowania
        </Modal>
    );
};

export default ResetPasswordModal;