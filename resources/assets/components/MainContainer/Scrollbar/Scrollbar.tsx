import useResize from '@/hooks/useResize';
import React, { createContext, useContext, useEffect, useRef, useState } from 'react';
import styles from './Scrollbar.scss';

export interface IScrollbarContext {
    containerRef?: React.MutableRefObject<any>;
}

export const ScrollbarContext = createContext<IScrollbarContext>({});

export const ScrollbarProvider: React.FC = (props) => {
    const containerRef = useRef(null);

    return (
        <ScrollbarContext.Provider value={{ containerRef }}>
            {props.children}
        </ScrollbarContext.Provider>
    );
};

export const Scrollbar = () => {
    const { containerRef } = useContext(ScrollbarContext);
    const scrollbarRef = useRef<HTMLDivElement>(null);

    const [thumbLength, setThumbLength] = useState(0);
    const [thumbPosition, setThumbPosition] = useState(0);

    const scrollbarHeight = useResize(scrollbarRef).height;
    const containerHeight = useResize(containerRef as React.MutableRefObject<any>).height;

    useEffect(() => {
        const updateScroll = () => {
            const scrollHeight = containerRef?.current?.scrollHeight;
            const scrollTop = containerRef?.current?.scrollTop;

            setThumbLength(containerHeight * scrollbarHeight / scrollHeight);
            setThumbPosition(scrollTop / scrollHeight * 100);
        };

        if (containerRef && containerRef.current) {
            updateScroll();

            containerRef.current.addEventListener('scroll', updateScroll, false);

            return () => {
                containerRef.current.removeEventListener('scroll', updateScroll, false);
            };
        }
    }, [containerRef, scrollbarHeight, containerHeight]);

    return (
        <div className={styles.scrollbar} ref={scrollbarRef}>
            <div className={styles.thumb} style={{
                height: thumbLength + 'px',
                top: thumbPosition + '%'
            }}></div>
        </div>
    );
};

const useScrollbar = () => {
    return useContext(ScrollbarContext);
};

export default useScrollbar;