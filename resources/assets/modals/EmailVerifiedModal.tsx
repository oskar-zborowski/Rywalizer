import { OrangeButton } from '@/components/Form/Button/Button';
import Modal from '@/components/Modal/Modal';
import React, { useState } from 'react';

const EmailVerifiedModal: React.FC = () => {
    const [isOpen, setIsOpen] = useState(true);

    return (
        <Modal
            title="Weryfikacja maila"
            isOpen={isOpen}
            onClose={() => setIsOpen(false)}
            width="450px"
            footerItems={[
                <OrangeButton key="2" onClick={() => setIsOpen(false)}>Zamknij</OrangeButton>
            ]}
        >
            <div style={{ fontSize: '12px', color: '#a1a1a1'}}>
                Dziękujemy za potwierdzenie adresu e-mail.
            </div>
        </Modal>
    );
};

export default EmailVerifiedModal;