import Flexbox from '@/components/Flexbox/Flexbox';
import { OrangeButton } from '@/components/Form/Button/Button';
import Input from '@/components/Form/Input/Input';
import Link from '@/components/Link/Link';
import Modal from '@/components/Modal/Modal';
import modalsStore from '@/store/ModalsStore';
import { observer } from 'mobx-react';
import React, { useState } from 'react';

const PartnerModal: React.FC = observer(() => {
    const [isLoading, setIsLoading] = useState(false);

    const saveNewPartner = () => {
        console.log('asdad');
    };

    return (
        <Modal
            title="Zostań partnerem"
            isOpen={modalsStore.isPartnerModalEnabled}
            onClose={() => modalsStore.setIsPartnerModalEnabled(false)}
            width="450px"
            isLoading={isLoading}
            footerItems={[
                <OrangeButton key="2" onClick={() => saveNewPartner()}>Zostań partnerem</OrangeButton>
            ]}
        >
            <Flexbox flexDirection="column" gap="10px">
                <Input label="Nazwa Firmy" />
                <Flexbox gap="10px">
                    <Input label="E-mail" />
                    <Input label="Telefon" />
                </Flexbox>
                <Input label="Strona internetowa" />
                <Input label="Facebook" />
                <Input label="Instagram" />
                <div style={{ fontSize: '12px', color: '#a1a1a1' }}>
                    Rejestrując się, akceptujesz&nbsp;<Link fixedColor>regulamin</Link> oraz&nbsp;
                    <Link fixedColor>politykę prywatyności</Link> serwisu nasza-nazwa.pl.
                </div>
            </Flexbox>
        </Modal>
    );
});

export default PartnerModal;