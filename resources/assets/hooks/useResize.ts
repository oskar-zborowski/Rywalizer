import { useEffect, useState } from 'react';

const useResize = (ref: React.RefObject<any>) => {
    const [width, setWidth] = useState<number>(0);
    const [height, setHeight] = useState<number>(0);

    useEffect(() => {
        setWidth(ref.current?.offsetWidth);
        setHeight(ref.current?.offsetHeight);

        const handleResize = () => {
            setWidth(ref.current?.offsetWidth);
            setHeight(ref.current?.offsetHeight);
        };

        window.addEventListener('resize', handleResize);

        return () => {
            window.removeEventListener('resize', handleResize);
        };
    }, [ref]);

    return { width, height };
};

export default useResize;