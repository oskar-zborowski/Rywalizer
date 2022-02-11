import Dropdown from '../../components/Controls/Dropdown/Dropdown';
import Component, { el } from '../../components/Component';
import styles from './Topbar.scss';

export default class Topbar extends Component {

    public constructor() {
        super();

        this._vdom = el(`div.${styles.topbar}`, [
            el(`div.${styles.logo}`, 'LOGO'),
            el(`nav.${styles.links}`, [
                el('span', 'Obiekty sportowe'),
                new Dropdown(),
                new Dropdown()
            ])
        ]);

        // <div className={styles.topbar}>
        //     <div className={styles.logo}><Link to="/">LOGO</Link></div>
        //     <nav className={styles.links}>
        //         <span>Obiekty sportowe</span>
        //         <Dropdown
        //             transparent
        //             placeholder="Ogłoszenia"
        //             isOpen={eventsDropdownOpen}
        //             handleIsOpenChange={(isOpen) => setEventsDropdownOpen(isOpen)}
        //         >
        //             <Link to="/"><DropdownRow><span>Lista ogłoszeń</span></DropdownRow></Link>
        //             <Link to="/ogloszenia/dodaj"><DropdownRow><span>Dodaj ogłoszenie</span></DropdownRow></Link>
        //         </Dropdown>
        //         <Dropdown
        //             transparent
        //             placeholder="Współpraca"
        //             isOpen={facilitiesDropdownOpen}
        //             handleIsOpenChange={(isOpen) => setFacilitiesDropdownOpen(isOpen)}
        //         >
        //             <Link to="/obiekty/1"><DropdownRow><span>TEST</span></DropdownRow></Link>
        //         </Dropdown>
        //     </nav>
        // </div >
    }

}