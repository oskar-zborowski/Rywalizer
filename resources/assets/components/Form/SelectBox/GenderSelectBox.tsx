import { IGender } from '@/api/getGenders';
import React from 'react';
import SelectBox, { IOption, ISelectBoxProps } from './SelectBox';

export interface IGenderSelectBox extends Omit<ISelectBoxProps<IGender>, 'options'> {
    genders: IGender[];
}

const GenderSelectBox: React.FC<IGenderSelectBox> = ({ genders, ...props }) => {
    const options = genders.map(gender => {
        const option: IOption<IGender> = {
            text: gender.name,
            value: gender
        };

        return option;
    });

    return (
        <SelectBox options={options} {...props} />
    );
};

export default GenderSelectBox;