import React, { useState } from 'react';
import Dropdown, { DropdownRow, IDropdownProps } from '../Dropdown/Dropdown';
import styles from './SelectBox.scss';
import dropdownStyles from '../Dropdown/Dropdown.scss';

export interface IOption<T = any> {
    value: T;
    text: string;
    isSelected?: boolean;
}

export interface SelectboxProps<T = any> extends IDropdownProps {
    multiselect?: boolean;
    options?: IOption<T>[];
    onChange?: (options: IOption<T>[], selectedOptions: IOption<T>[]) => void;
    rowFactory?: (option: IOption<T>) => React.ReactNode,
    searchBar?: {
        getOptions: (searchString: string) => IOption<T>[] | Promise<IOption<T>[]>
        debounceTimeMs?: number;
    }
}

function Selectbox<T = any>(props: SelectboxProps<T>) {
    const {
        multiselect = false,
        options = [],
        onChange,
        searchBar,
        rowFactory = op => (<span>{op.text}</span>),
        ...dropdownProps
    } = props;

    if (multiselect) {

    }

    return (
        <Dropdown {...dropdownProps}>
            {options.map((op, i) => {
                const checkboxClass = styles.checkbox + ' ' + (op.isSelected ? styles.checked : '');

                return (
                    <DropdownRow>
                        <div className={styles.rowContent}>{rowFactory(op)}</div>
                        <div className={checkboxClass}></div>
                    </DropdownRow>
                );
            })}
        </Dropdown>
    );
}

export default Selectbox;