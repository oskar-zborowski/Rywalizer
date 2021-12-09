import ProgressBar from '@/components/ProgressBar/ProgressBar';
import React from 'react';
import styles from './Calendar.scss';

const colors = [
    '#7ab661', '#7ab661', '#ffd653', '#bb2121'
];

const Calendar: React.FC = () => {
    return (
        <div className={styles.wrapper}>
            <table className={styles.calendar}>
                <thead>
                    <tr>
                        <th>Pon.</th>
                        <th>Wt.</th>
                        <th>Śr.</th>
                        <th>Czw.</th>
                        <th>Pt.</th>
                        <th>Sob.</th>
                        <th>Niedz.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div className={styles.cell}>
                                <span>1</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>2</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>3</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>4</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>5</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>6</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>7</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div className={styles.cell}>
                                <span>8</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>9</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>10</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>11</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>12</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>13</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>14</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div className={styles.cell}>
                                <span>15</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>16</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>17</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>18</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>19</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>20</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>21</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div className={styles.cell}>
                                <span>22</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>23</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>24</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>25</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>26</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>27</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>28</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div className={styles.cell}>
                                <span>29</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell}>
                                <span>30</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell + ' ' + styles.disabled}>
                                <span>1</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell + ' ' + styles.disabled}>
                                <span>2</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell + ' ' + styles.disabled}>
                                <span>3</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell + ' ' + styles.disabled}>
                                <span>4</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                        <td>
                            <div className={styles.cell + ' ' + styles.disabled}>
                                <span>5</span>
                                <ProgressBar colors={colors} progress={Math.random() * 100} />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    );
};

export default Calendar;