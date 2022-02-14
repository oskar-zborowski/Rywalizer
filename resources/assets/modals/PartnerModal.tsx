import savePartner from '@/api/savePartner';
import Flexbox from '@/components/Flexbox/Flexbox';
import { OrangeButton } from '@/components/Form/Button/Button';
import Input from '@/components/Form/Input/Input';
import Link from '@/components/Link/Link';
import Modal from '@/components/Modal/Modal';
import modalsStore from '@/store/ModalsStore';
import userStore from '@/store/UserStore';
import { runInAction } from 'mobx';
import { observer } from 'mobx-react';
import React, { useEffect, useRef, useState } from 'react';
import { useNavigate } from 'react-router-dom';

const PartnerModal: React.FC = observer(() => {
    const [isLoading, setIsLoading] = useState(false);
    const [businessName, setBusinessName] = useState('');
    const [contactEmail, setEmail] = useState('');
    const [telephone, setTelephone] = useState('');
    const [website, setWebsite] = useState('');
    const [facebookProfile, setFacebook] = useState('');
    const [instagramProfile, setInstagram] = useState('');

    const navigateTo = useNavigate();

    const saveNewPartner = async () => {
        setIsLoading(true);
        await savePartner({
            businessName: businessName || undefined,
            contactEmail: contactEmail || undefined,
            telephone: telephone || undefined,
            website: website || undefined,
            facebookProfile: facebookProfile || undefined,
            instagramProfile: instagramProfile || undefined
        });

        setIsLoading(false);
        runInAction(() => {
            userStore.user.isPartner = true;
        });

        modalsStore.setIsPartnerModalEnabled(false);
        navigateTo('/partnerstwo');
    };

    useEffect(() => {
        if (userStore.user) {
            setEmail(userStore.user.email || '');
            setTelephone(userStore.user.phoneNumber || '');
            setWebsite(userStore.user.website || '');
            setFacebook(userStore.user.facebookProfile || '');
            setInstagram(userStore.user.instagramProfile || '');
        }
    }, [userStore.user, modalsStore.isPartnerModalEnabled]);

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
                <Input label="Nazwa Firmy" value={businessName} onChange={v => setBusinessName(v)} />
                <Flexbox gap="10px">
                    <Input label="E-mail" value={contactEmail} onChange={v => setEmail(v)} />
                    <Input label="Telefon" value={telephone} onChange={v => setTelephone(v)} />
                </Flexbox>
                <Input label="Strona internetowa" value={website} onChange={v => setWebsite(v)} />
                <Input label="Facebook" value={facebookProfile} onChange={v => setFacebook(v)} />
                <Input label="Instagram" value={instagramProfile} onChange={v => setInstagram(v)} />
                <div style={{ fontSize: '12px', color: '#a1a1a1' }}>
                    Rejestrując się jako partner, akceptujesz&nbsp;<Link fixedColor>regulamin</Link> oraz&nbsp;
                    <Link fixedColor>politykę prywatyności</Link> serwisu nasza-nazwa.pl.
                </div>
            </Flexbox>
        </Modal>
    );
});

export default PartnerModal;