import Component, { el } from '@/app/Component';
import styles from './Button.scss';

export type ButtonConfig = {
    text: string;
} | string;

export class Button extends Component {

    private config: ButtonConfig;

    public constructor(config: ButtonConfig) {
        super();

        this.config = config;
    }

    protected render(): JQuery {
        const button = el('button');

        if (typeof this.config === 'string') {
            button.append(this.config);
        } else {
            button.append(this.config.text);
        }

        return button;
    }

}

export class OrangeButton extends Button {

    public render(): JQuery {
        return super.render().addClass(styles.orangeButton);
    }

}

export class GrayButton extends Button {

    public render(): JQuery {
        return super.render().addClass(styles.grayButton);
    }

}