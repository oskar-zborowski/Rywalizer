
import Component, { el } from '@/app/Component';
import styles from './Scrollbar.scss';

export class Scrollbar extends Component {

    private scrollbar: JQuery;
    private thumb: JQuery;
    private targetContainer: JQuery;

    public render(): JQuery {
        this.scrollbar = el(`div.${styles.scrollbar}`,
            this.thumb = el(`div.${styles.thumb}`)
        );

        return this.scrollbar;
    }

    public setTargetContainer(container: JQuery): void {
        this.targetContainer = container;
        this.targetContainer.on('scroll', () => this.updateThumb());
        this.updateThumb();
    }

    public updateThumb() {
        const scrollbarHeight = this.scrollbar?.outerHeight();
        const containerHeight = this.targetContainer?.outerHeight();
        const scrollHeight = this.targetContainer?.get(0).scrollHeight;
        const scrollTop = this.targetContainer?.get(0).scrollTop;

        this.thumb?.css('height', (containerHeight * scrollbarHeight / scrollHeight) + 'px');
        this.thumb?.css('top', (scrollTop / scrollHeight * 100) + '%');
    }

}

const scrollbar = new Scrollbar();
export default scrollbar;