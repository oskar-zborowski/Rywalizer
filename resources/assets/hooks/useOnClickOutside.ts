import { useEffect } from 'react';

export const useOnClickOutside = (
    ref: React.RefObject<any>, 
    onClickOutside: (event: MouseEvent) => void,
    onClickInside?: (event: MouseEvent) => void
) => {
    useEffect(() => {
        const listener = (event: MouseEvent) => {
            if (!ref.current || ref.current.contains(event.target)) {
                onClickInside?.(event);
            } else {
                onClickOutside(event);
            }
        };
  
        document.addEventListener('mousedown', listener);
        document.addEventListener('touchstart', listener);
  
        return () => {
            document.removeEventListener('mousedown', listener);
            document.removeEventListener('touchstart', listener);
        };
    }, [ref, onClickOutside]);
};