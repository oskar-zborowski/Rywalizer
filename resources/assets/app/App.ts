import Content from '@/layout/Content/Content';
import scrollbar from '@/layout/Content/Scrollbar/Scrollbar';
import Footer from '@/layout/Footer/Footer';
import Topbar from '@/layout/Topbar/Topbar';
import './App.scss';

export default class App {

    public constructor() {
        const appContainer = $('#app');
        appContainer.append(
            new Topbar().node, 
            new Content().node, 
            new Footer().node
        );

        scrollbar.updateThumb();
    }

}