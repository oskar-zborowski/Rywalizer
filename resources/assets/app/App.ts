import Content from '@/layout/Content/Content';
import Footer from '@/layout/Footer/Footer';
import Topbar from '@/layout/Topbar/Topbar';
import './App.scss';
import Component, { el } from './Component';

export default class App extends Component {

    protected render(): JQuery<HTMLElement> {
        return el(
            new Topbar(),
            new Content(),
            new Footer()
        );
    }

}