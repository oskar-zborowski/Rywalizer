import { ISport } from '@/api/getSports';
import React from 'react';
import SelectBox, { IOption, ISelectBoxProps } from './SelectBox';

export interface ISportsSelectBoxProps extends Omit<ISelectBoxProps<ISport>, 'options'> {
    sports: ISport[];
}

const SportsSelectBox: React.FC<ISportsSelectBoxProps> = ({ sports, ...props }) => {
    const options = sports.map(sport => {
        const option: IOption<ISport> = {
            text: sport.name,
            value: sport
        };

        return option;
    });

    return (
        <SelectBox options={options} {...props} minWidth={250}/>
    );
};

export default SportsSelectBox;