import Component, { ComponentNode } from '../../Component';
import Input, { IInputProps } from '../Input/Input';

export interface IDropdownProps extends IInputProps {
    content?: ComponentNode;
}

export default class Dropdown extends Input {

    public constructor(props?: IDropdownProps) {
        super(props);

        const { content } = props ?? {};
    }

    public open() {
        //
    }

    public hide() {
        //
    }

}