import React from 'react';

export interface FlexboxProps {
    alignItems?: React.CSSProperties['alignItems']
    justifyContent?: React.CSSProperties['justifyContent'];
    flexDirection?: React.CSSProperties['flexDirection'];
    gap?: React.CSSProperties['gap'];
    width?: React.CSSProperties['width'];
}

const Flexbox: React.FC<FlexboxProps> = (props) => {
    const style: React.CSSProperties = {
        display: 'flex',
        ...props
    };

    return (
        <div style={style}>{props.children}</div>
    );
};

export default Flexbox;