import Component, { el } from '@/app/Component';
import styles from './EventTile.scss';
import chroma from 'chroma-js';

export interface IEventTileConfig {
    imageSrc: string;
    price: number;
    date: Date;
    locationName: string;
    address: string;
    soldTickets: number;
    availableTickets: number;
}

//TODO nieliniowa funckja, patrz nizej
const colors = chroma.scale(['#7ab661', '#7ab661', '#ffd653', '#bb2121']).colors(20);

export default class EventTile extends Component {

    private config: IEventTileConfig;

    public constructor(config: IEventTileConfig) {
        super();

        this.config = config;
    }

    protected render(): JQuery<HTMLElement> {
        const {
            imageSrc, price, date, locationName,
            address, soldTickets, availableTickets
        } = this.config;

        //TODO zmiana nazwy percent na coś sensowniejszego
        const ratio = soldTickets / availableTickets;
        const percent = ratio * 100;

        //TODO nieliniowa funckja - powinno być znacznie więcej zielonego ??
        const color = colors[Math.trunc(ratio * (colors.length - 1))];

        return el(`div.${styles.eventTile}`,
            el(`img.${styles.image}`).attr('src', imageSrc),
            el(`div.${styles.detailsRow}`,
                el(`span.${styles.locationName}`, locationName),
                el(`span.${styles.price}`, (price / 100).toFixed(2) + ' zł')
            ),
            el(`div.${styles.detailsRow}`,
                el(`span.${styles.address}`, address),
                el(`span.${styles.date}`, date.toLocaleDateString())
            ),
            el(`div.${styles.divider}`),
            el(`div.${styles.detailsRow}`,
                el(`div.${styles.busyBar}`,
                    el(`div.${styles.innerBar}`).css({
                        width: percent + '%',
                        backgroundColor: color
                    })
                ),
                el(`span.${styles.date}`, soldTickets + '/' + availableTickets)
            )
        );
    }

}