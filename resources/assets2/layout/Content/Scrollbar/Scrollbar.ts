import Component, { el } from '../../../components/Component';
import styles from './Scrollbar.scss';

export default class Scrollbar extends Component {

    public constructor() {
        super();

        // <div className={styles.scrollbar} ref={scrollbarRef}>
        //     <div className={styles.thumb} style={{
        //         height: thumbLength + 'px',
        //         top: thumbPosition + '%'
        //     }}></div>
        // </div>

        this._vdom = el(`div.${styles.scrollbar}`, [
            el(`div.${styles.thumb}`)
        ]);
    }

}