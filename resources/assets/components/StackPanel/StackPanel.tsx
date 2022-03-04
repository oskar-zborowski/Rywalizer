import React from 'react';
import styles from './StackPanel.scss';

export interface IStackPanelProps {
    gap?: number;
    padding?: React.CSSProperties['padding'],
    margin?: React.CSSProperties['margin'],
    vertical?: boolean;
    fluid?: boolean;
    wrap?: boolean
}

const StackPanel: React.FC<IStackPanelProps> = ({ children, gap = 20, padding, margin, vertical, fluid, wrap = false }) => {
    const style: React.CSSProperties = {
        gap: gap + 'px',
        width: 'min-content',
        margin,
        padding,
        flexWrap: wrap ? 'wrap' : 'nowrap'
    };

    if (vertical) {
        style.flexDirection = 'column';
    }

    if (fluid) {
        style.width = 'initial';
    }

    return (
        <div className={styles.stackPanel} style={style}>
            {children}
        </div>
    );
};

export default StackPanel;