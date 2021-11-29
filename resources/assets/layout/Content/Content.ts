import Component, { el } from '@/app/Component';
import { GrayButton, OrangeButton } from '@/components/form/Button/Button';
import { MapViewer } from '@/components/MapViewer/MapViewer';
import styles from './Content.scss';
import scrollbar from './Scrollbar/Scrollbar';

export default class Content extends Component {

    public render(): JQuery<HTMLElement> {
        const container = el(`main.${styles.mainContainer}`,
            el(`div.${styles.authButtons}`,
                new OrangeButton('Zaloguj się').node,
                new GrayButton('Zarejestruj się').node,
            ),
            el('div').css('height', '4000px') // Overflow test
        );

        const content = el(`div.${styles.content}`,
            container,
            scrollbar.node,
            new MapViewer().node
        );

        scrollbar.setTargetContainer(container);

        return content;
    }

}