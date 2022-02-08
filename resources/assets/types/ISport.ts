import chroma from 'chroma-js';

export interface ISport {
    id: number;
    name: string;
    icon: string;
    color: chroma.Color;
}