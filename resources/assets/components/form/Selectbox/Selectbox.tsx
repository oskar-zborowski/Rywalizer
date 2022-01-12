import React, { useState } from 'react';
import styles from './Selectbox.scss';

export interface IOption<T = any> {
    value: T;
    text: string;
    isSelected?: boolean;
}

export interface SelectboxProps<T = any> {
    isOpen: boolean;
    onClose: () => void;
    multiselect?: boolean;
    options?: IOption<T>[];
    onChange?: (selectedOptions: IOption<T>[]) => void;
    searchBar?: {
        getOptions: (searchString: string) => IOption<T>[] | Promise<IOption<T>[]>
        debounceTimeMs?: number;
    }
}

function Selectbox<T = number>(props: SelectboxProps<T>) {
    const {
        isOpen,
        onClose,
        multiselect = false,
        options = [],
        onChange,
        searchBar
    } = props;

    const initialValue: IOption<T>[] = [];

    if (multiselect) {
        
    }

    const [selectedOptions, setSelectedOptions] = useState<IOption<T>[]>(initialValue);

    return (
        <div className={styles.input}>
        </div>
    );
}

export default Selectbox;