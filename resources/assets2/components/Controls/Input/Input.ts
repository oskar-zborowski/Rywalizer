import Component, { el } from '../../Component';
import styles from './Input.scss';

export interface IInputProps {
    label?: string;
}

export default abstract class Input extends Component {

    protected inputWrapperVdom: JQuery;

    public constructor(props?: IInputProps) {
        super();

        const { label } = props ?? {};

        this._vdom = el(`div.${styles.wrapper}`, [
            el(`div.${styles.label}`, label),
            this.inputWrapperVdom = el(`div.${styles.inputWrapper}`)
        ]);
    }

}