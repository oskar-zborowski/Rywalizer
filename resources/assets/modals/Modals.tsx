import React, { Fragment } from 'react';
import LoginModal from './LoginModal';
import RegisterModal from './RegisterModal';
import RemindPasswordModal from './RemindPasswordModal';

const Modals: React.FC = () => {
    return (
        <Fragment>
            <LoginModal/>
            <RemindPasswordModal/>
            <RegisterModal/>
        </Fragment>
    );
};

export default Modals;