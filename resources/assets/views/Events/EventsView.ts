import Component, { el } from '@/app/Component';
import styles from './EventsView.scss';
import faker from 'faker';
import EventTile, { IEventTileConfig } from './EventTile';
import scrollbar from '@/layout/Content/Scrollbar/Scrollbar';

export default class EventsView extends Component {

    protected render(): JQuery<HTMLElement> {
        faker.locale = 'pl';

        const tilesCount = Math.trunc(Math.random() * 40) + 5;
        const fakeData: IEventTileConfig[] = [];

        for (let i = 0; i < tilesCount; i++) {
            const availableTickets = Math.round(Math.random() * 100) + 10;
            const soldTickets = Math.round(Math.random() * availableTickets);

            fakeData.push({
                availableTickets,
                soldTickets,
                price: 3500,
                address: faker.address.cityName(),
                locationName: faker.address.cityName(),
                imageSrc: faker.image.image(),
                date: new Date()
            });
        }

        const tilesContainer = el(`div.${styles.containerWrapper}`,
            el(`div.${styles.eventTilesContainer}`,
                ...fakeData.map(data => new EventTile(data))
            )
        );

        scrollbar.setTargetContainer(tilesContainer);

        return el(`div.${styles.eventsView}`,
            el(`div.${styles.filters}`),
            tilesContainer
        );
    }

}