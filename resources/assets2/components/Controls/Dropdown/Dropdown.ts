import Component, { ComponentNode, el } from '../../Component';
import Input, { IInputProps } from '../Input/Input';
import styles from './Dropdown.scss';
import anime from 'animejs';

export interface IDropdownProps extends IInputProps {
    content?: ComponentNode;
}

export default class Dropdown extends Input {

    protected itemsContainerVdom: JQuery;
    protected _isOpen = false;

    public constructor(content?: ComponentNode, props?: IDropdownProps) {
        super(props);

        this.inputWrapperVdom.append(
            el(`div.${styles.dropdown}`)
        );

        this.itemsContainerVdom = el(`div.${styles.itemsContainer}`, content);
        this.itemsContainerVdom.insertAfter(this.inputWrapperVdom).hide();

        this.inputWrapperVdom.on('click', () => {
            if (this._isOpen) {
                this.hide();
            } else {
                this.open();
            }
        });
    }

    public open() {
        this._isOpen = true;
        this.itemsContainerVdom.show();

        anime({
            targets: this.itemsContainerVdom.get(0),
            opacity: [0, 1],
            translateY: [-15, 0],
            duration: 200,
            easing: 'cubicBezier(0.45, 0, 0.55, 1)'
        });
    }

    public hide() {
        this._isOpen = false;

        anime({
            targets: this.itemsContainerVdom.get(0),
            opacity: [1, 0],
            translateY: [0, 15],
            duration: 200,
            easing: 'cubicBezier(0.45, 0, 0.55, 1)',
            complete: () => this.itemsContainerVdom.hide()
        });
    }

}