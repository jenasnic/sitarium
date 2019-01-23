import axios from 'axios';
import { displayPopup, displayFormPopup } from '../../component/popup';

export default class QuizResolver {
    responseCount;
    resolveUrl;
    popupDelay;

    /**
     * @param int responseCount
     * @param string resolveUrl Callback URL when resolving quiz.
     * @param int popupDelay Time before closing popup automatically.
     */
    constructor(quizId, responseCount, resolveUrl, popupDelay) {
        this.responseCount = responseCount;
        this.resolveUrl = resolveUrl;
        this.popupDelay = popupDelay;
    };

    /**
     * Allows to check if number of responses found match with quiz response count.
     */
    allResponsesFound() {
        return (document.querySelectorAll('#quiz-responses li[data-response]').length === this.responseCount);
    };

    /**
     * Allows to submit quiz resolution to check all responses found....
     */
    handleQuizResolution() {
        const responses = [...document.querySelectorAll('#quiz-responses li[data-response]')].map((element) => {
            return element.innerHTML;
        });

        axios.post(this.resolveUrl, {responses: JSON.stringify(responses)})
            .then((response) => {
                displayPopup(response.data.message, {autoCloseDelay: this.popupDelay});
                if (!response.data.success) {
                    return;
                }

                const actionElement = document.getElementById('action-wrapper');
                actionElement.parentNode.removeChild(actionElement);
            })
        ;
    }
}
