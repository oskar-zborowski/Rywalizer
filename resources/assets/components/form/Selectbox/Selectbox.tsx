import React, { useEffect, useState } from 'react';
import Dropdown, { DropdownRow, IDropdownProps } from '../Dropdown/Dropdown';
import Input from '../Input/Input';
import styles from './SelectBox.scss';
import slugify from 'slugify';

export interface IOption<T = any> {
    value: T;
    text: string;
    isSelected?: boolean;
}

export interface ISelectBoxProps<T = any> extends Omit<IDropdownProps, 'isOpen' | 'handleIsOpenChange'> {
    multiselect?: boolean;
    options?: IOption<T>[];
    handleOptionsChange?: (options: IOption<T>[]) => void;
    onChange?: (selectedOptions: IOption<T>[]) => void;
    rowFactory?: (option: IOption<T>) => React.ReactNode,
    searchBar?: boolean;
    // searchBar?: {
    //     getOptions?: (searchString: string) => IOption<T>[] | Promise<IOption<T>[]>
    //     debounceTimeMs?: number;
    // }
}

function SelectBox<T = any>(props: ISelectBoxProps<T>) {
    const {
        multiselect = false,
        options = [],
        onChange,
        searchBar,
        rowFactory = op => (<span>{op.text}</span>),
        placeholder,
        ...dropdownProps
    } = props;

    if (multiselect) {
        //TODO
    }

    const [isOpen, setIsOpen] = useState(false);
    const [hiddenOptionsIds, setHiddenOptionsIds] = useState<number[]>([]);
    const [selectedOptionsIds, setSelectedOptionsIds] = useState<number[]>(() => {
        const selectedOptions = [];

        options.forEach((option, i) => {
            if (option.isSelected === true) selectedOptions.push(i);
        });

        return selectedOptions;
    });

    const onClick = (i: number) => {
        setSelectedOptionsIds([i]);
        onChange?.([options[i]]);
        setIsOpen(false);
    };

    // const { getOptions, debounceTimeMs } = searchBar ?? {};

    const onSearchQueryChange = async (query: string) => {
        const hiddenOptions = [];

        options.forEach((option, i) => {
            if (!slugify(option.text.toLowerCase()).includes(query)) hiddenOptions.push(i);
        });

        setHiddenOptionsIds(hiddenOptions);
    };

    return (
        <Dropdown
            isOpen={isOpen}
            handleIsOpenChange={(isOpen) => {
                setIsOpen(isOpen);
                setHiddenOptionsIds([]);
            }}
            placeholder={placeholder || (options[selectedOptionsIds[0]]?.text ?? '- Wybierz -')}
            {...dropdownProps}
        >
            {searchBar && <Input
                style={{ marginBottom: '10px' }}
                onChange={(val) => onSearchQueryChange(slugify(val.toLowerCase()))}
            />}
            {options.map((op, i) => {
                if (hiddenOptionsIds.includes(i)) {
                    return null;
                }

                const isSelected = selectedOptionsIds.includes(i);
                const checkboxClass = styles.checkbox + ' ' + (isSelected ? styles.checked : '');

                return (
                    <DropdownRow key={i} onClick={() => onClick(i)}>
                        <div className={styles.rowContent}>{rowFactory(op)}</div>
                        <div className={checkboxClass}></div>
                    </DropdownRow>
                );
            })}
        </Dropdown>
    );
}

export default SelectBox;