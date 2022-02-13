import React, { Fragment } from 'react';
import LoginModal from './LoginModal';
import PartnerModal from './PartnerModal';
import RegisterModal from './RegisterModal';
import RemindPasswordModal from './RemindPasswordModal';

const Modals: React.FC = () => {
    return (
        <Fragment>
            <LoginModal/>
            <RemindPasswordModal/>
            <RegisterModal/>
            <PartnerModal/>
        </Fragment>
    );
};

export default Modals;