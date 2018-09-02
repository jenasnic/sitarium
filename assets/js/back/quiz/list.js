import tableDragger from 'table-dragger'
import { displayModal } from "../popup";

/**
 * Allows to reorder quiz after drag and drop on list.
 * NOTE : new order is directly saved in BO using ajax call...
 *
 * @param from Index of first row we are changing order (starting 1 for first row, not 0).
 * @param end Index of last row we are changing order.
 */
const reorderQuizRows = (from, to) => {
    let reorderedIds = [];
    const rows = document.querySelectorAll('#quiz-list tbody tr');

    for (let i = from; i <= to; i++) {
        const row = rows[i - 1];
        reorderedIds.push({id: parseInt(row.getAttribute('data-id')), rank: i});
    }

    fetch('/admin/quiz/reorder', {
        credentials: 'same-origin',
        method: 'POST',
        body: JSON.stringify(reorderedIds),
    });
};

const initQuizList = () => {
    // Define action when user click on new quiz
    document.getElementById('new-quiz-button').addEventListener('click', (event) => {
        displayModal('new-quiz-modal');
    });

    // Define behavior for new quiz form
    document.querySelector('#new-quiz-modal input[type=text]').addEventListener('keyup', (event) => {
        const name = event.target.value;
        if (name.length > 2) {
            document.querySelector('#new-quiz-modal input[type=submit]').disabled = false;
            if (event.keyCode == 13) {
                document.getElementById('new-quiz-form').submit();
            }
        } else {
            document.querySelector('#new-quiz-modal input[type=submit]').disabled = true;
        }
    });

    const dragger = tableDragger(document.getElementById('quiz-list'), {
        mode: 'row',
        onlyBody: true,
    });

    dragger.on('drop', (from, to) => {
        const start = Math.min(from, to);
        const end = Math.max(from, to);
        reorderQuizRows(start, end);
    });
};

document.getElementById('quiz-list') && initQuizList();
