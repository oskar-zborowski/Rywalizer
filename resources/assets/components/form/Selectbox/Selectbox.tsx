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
    options: IOption<T>[];
    handleOptionsChange: (options: IOption<T>[]) => void;
    handleSelectedOptionsChange: (selectedOptions: IOption<T>[]) => void;
    multiselect?: boolean;
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
        handleOptionsChange,
        handleSelectedOptionsChange,
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

    const onClick = (i: number) => {
        const isSelected = !options[i].isSelected;
        options.forEach(option => option.isSelected = false);
        options[i].isSelected = isSelected;

        handleOptionsChange(options);
        handleSelectedOptionsChange(isSelected ? [options[i]] : []);

        setIsOpen(false);
    };

    const onSearchQueryChange = async (query: string) => {
        const hiddenOptions = [];

        options.forEach((option, i) => {
            if (!slugify(option.text.toLowerCase()).includes(query)) hiddenOptions.push(i);
        });

        setHiddenOptionsIds(hiddenOptions);
    };

    const selectedOptions = options.filter(option => option.isSelected);

    return (
        <Dropdown
            isOpen={isOpen}
            handleIsOpenChange={(isOpen) => {
                setIsOpen(isOpen);
                setHiddenOptionsIds([]);
            }}
            placeholder={placeholder || (selectedOptions[0]?.text ?? '- Wybierz -')}
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

                const checkboxClass = styles.checkbox + ' ' + (op.isSelected ? styles.checked : '');

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

export function useSelectBox<T = any>(
    initialOptions: IOption<T>[] = [],
    onSelectedOptionsChange?: (selectedOptions: T[]) => void
) {
    const [options, setOptions] = useState<IOption<T>[]>(initialOptions);
    const [selectedOptions, setSelectedOptions] = useState<IOption<T>[]>(initialOptions);

    return {
        options,
        setOptions,
        selectedOptions,
        handleOptionsChange: (options: IOption<T>[]) => setOptions(options),
        handleSelectedOptionsChange: (selectedOptions: IOption<T>[]) => {
            setSelectedOptions(selectedOptions);
            onSelectedOptionsChange?.(selectedOptions.map(opt => opt.value));
        },
        select: (predicate: number | ((optionValue: T) => boolean)) => {
            if (predicate === null || predicate === undefined) {
                setOptions(options => {
                    options.forEach(opt => {
                        opt.isSelected = false;
                    });

                    return [...options];
                });
            } else if (typeof predicate === 'number') {
                setOptions(options => {
                    if (options[predicate]) {
                        options[predicate].isSelected = true;
                    }

                    return [...options];
                });
            } else {
                setOptions(options => {
                    return options.map(opt => {
                        opt.isSelected = predicate(opt.value);
                        return opt;
                    });
                });
            }
        }
    };
}