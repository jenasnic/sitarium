import axios from 'axios';
import tableDragger from 'table-dragger'
import { displayModal } from '../popup';

/**
 * Allows to initialize actions on quiz list.
 */
const initActions = () => {
    document.getElementById('new-quiz-button').addEventListener('click', (event) => {
        displayModal(document.getElementById('new-quiz-form'));
    });

    initNewQuizFormBehavior();
    initListReordering();
};

/**
 * Allows to define behavior for new quiz form.
 */
const initNewQuizFormBehavior = () => {
    const newQuizForm = document.getElementById('new-quiz-form');

    newQuizForm.querySelector('input[type=text]').addEventListener('keyup', (event) => {
        const name = event.target.value;
        if (name.length > 2) {
            newQuizForm.querySelector('input[type=submit]').disabled = false;
            if ('Enter' === event.key || 13 === event.keyCode) {
                newQuizForm.submit();
            }
        } else {
            newQuizForm.querySelector('input[type=submit]').disabled = true;
        }
    });
};

/**
 * Allows to initialize drag and drop feature to reorder quiz list.
 */
const initListReordering = () => {
    const dragger = tableDragger(document.getElementById('quiz-list'), {
        mode: 'row',
        onlyBody: true,
    });

    const reorderUrl = document.getElementById('quiz-list').dataset.reorderUrl;
    dragger.on('drop', (from, to) => {
        const start = Math.min(from, to);
        const end = Math.max(from, to);
        reorderQuizRows(reorderUrl, start, end);
    });
};

/**
 * Allows to reorder quiz after drag and drop on list.
 * NOTE : new order is directly saved in BO using ajax call...
 *
 * @param reorderUrl URL to call to reorder quiz.
 * @param from Index of first row we are changing order (starting 1 for first row, not 0).
 * @param end Index of last row we are changing order.
 */
const reorderQuizRows = (reorderUrl, from, to) => {
    let reorderedIds = [];
    const rows = [...document.querySelectorAll('#quiz-list tbody tr')];

    for (let i = from; i <= to; i++) {
        const row = rows[i - 1];
        reorderedIds.push({id: parseInt(row.getAttribute('data-id')), rank: i});
    }

    axios.post(reorderUrl, {reorderedIds: JSON.stringify(reorderedIds)})
        .then((response) => {
            if (!response.data.success) {
                displayModal(response.data.message);
            }
        })
    ;
};

document.getElementById('quiz-list') && initActions();
