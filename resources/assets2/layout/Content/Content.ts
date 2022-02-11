import Component, { el } from '../../components/Component';
import styles from './Content.scss';
import MainContainer from './MainContainer/MainContainer';
import MapViewer from './MapViewer/MapViewer';
import Scrollbar from './Scrollbar/Scrollbar';

export default class Content extends Component {

    public constructor() {
        super();

        this._vdom = el(`div.${styles.content}`, [
            new MainContainer(),
            new Scrollbar(),
            new MapViewer()
        ]);
    }

}