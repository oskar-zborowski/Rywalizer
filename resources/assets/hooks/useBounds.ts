import useResizeObserver from '@react-hook/resize-observer';
import React from 'react';
import { useEffect, useState } from 'react';

export interface IBounds {
    width: number;
    height: number;
    left: number;
    top: number;
    right: number;
    bottom: number;
}

const useBounds = (ref: React.RefObject<any>, callback?: (bounds: IBounds, absBounds: IBounds) => void) => {
    const [bounds, setBounds] = React.useState<IBounds>(null);
    const [absBounds, setAbsBounds] = React.useState<IBounds>(null);

    React.useLayoutEffect(() => {
        setBounds(ref.current?.getBoundingClientRect());
        setAbsBounds(ref.current?.getBoundingClientRect());
    }, [ref]);

    useResizeObserver(ref, (entry) => {
        const newAbsBounds = ref.current.getBoundingClientRect();
        setBounds(entry.contentRect);
        setAbsBounds(newAbsBounds);

        callback?.(entry.contentRect, newAbsBounds);
    });

    return {bounds, absBounds};
};

export default useBounds;