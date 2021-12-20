declare module '*.scss';
declare module '*.png';
declare module '*.jpg';

declare module '*.svg' {
    const content: React.FC<React.SVGAttributes<SVGElement>>;
    export default content;
}