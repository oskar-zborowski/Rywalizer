import ProgressBar from '@/components/ProgressBar/ProgressBar';
import React, { Fragment } from 'react';
import ReactTooltip from 'react-tooltip';
import styles from './Calendar.scss';

const colors = [
    '#7ab661', '#7ab661', '#ffd653', '#bb2121'
];

const Calendar: React.FC = () => {
    return (
        <Fragment>
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
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>1</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>2</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>3</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>4</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>5</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>6</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>7</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>8</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>9</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>10</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>11</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>12</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>13</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>14</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>15</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>16</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>17</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>18</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>19</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>20</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>21</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>22</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>23</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>24</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>25</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>26</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>27</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>28</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
                                    <span>29</span>
                                    <ProgressBar colors={colors} progress={Math.random() * 100} />
                                </div>
                            </td>
                            <td>
                                <div className={styles.cell} data-tip="Kliknij tutaj</br>i <b>zarezerwuj</b> miejsce">
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
            <ReactTooltip multiline={true} html={true} className={styles.tooltip}/>
        </Fragment>
    );
};

export default Calendar;