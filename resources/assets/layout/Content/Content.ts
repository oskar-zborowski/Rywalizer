import Component, { el } from '@/app/Component';
import { GrayButton, OrangeButton } from '@/components/form/Button/Button';
import { MapViewer } from '@/components/MapViewer/MapViewer';
import EventsView from '@/views/Events/EventsView';
import styles from './Content.scss';
import scrollbar from './Scrollbar/Scrollbar';

export default class Content extends Component {

    public render(): JQuery {
        const container = el(`main.${styles.mainContainer}`,
            el(`div.${styles.authButtons}`,
                new OrangeButton('Zaloguj się'),
                new GrayButton('Zarejestruj się'),
            ),
            new EventsView()
        );

        const content = el(`div.${styles.content}`,
            container,
            scrollbar,
            new MapViewer()
        );

        return content;
    }

}