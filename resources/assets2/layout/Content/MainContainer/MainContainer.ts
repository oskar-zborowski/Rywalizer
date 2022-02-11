import Component, { el } from '../../../components/Component';
import styles from './MainContainer.scss';

export default class MainContainer extends Component {

    public constructor() {
        super();

        this._vdom = el(`main.${styles.mainContainer}`, [
            'content'
        ]);
    }

}