import axios from 'axios';
import { displayPopup } from '../../component/popup';

export default class ResponseResolver {
    quizId;
    responseUrl;
    responseLocator;
    quizResolver;
    popupDelay;
    responseFoundMessage;
    responseAlreadyFoundMessage;

    /**
     * @param int quizId
     * @param string responseUrl Callback URL to submit response.
     * @param ResponseLocator responseLocator
     * @param QuizResolver quizResolver
     * @param int popupDelay Time before closing popup automatically.
     */
    constructor(quizId, responseUrl, responseLocator, quizResolver, popupDelay) {
        this.quizId = quizId;
        this.responseUrl = responseUrl;
        this.responseLocator = responseLocator;
        this.quizResolver = quizResolver;
        this.popupDelay = popupDelay;

        this.responseFoundMessage = document.getElementById('message-response-found').value;
        this.responseAlreadyFoundMessage = document.getElementById('message-response-already-found').value;

    };

    /**
     * Allows to submit response.
     * 
     * @param string response
     */
    submitResponse(response) {
        const parameters = {
            quizId: this.quizId,
            response: response
        };

        axios.post(this.responseUrl, parameters)
            .then((response) => {
                if (!response.data.success) {
                    displayPopup(response.data.message, {autoCloseDelay: this.popupDelay});
                    return;
                }

                // Check if response already found
                if (document.querySelectorAll(`li[data-response-id="${response.data.id}"]`).length > 0) {
                    displayPopup(this.responseAlreadyFoundMessage, {autoCloseDelay: this.popupDelay});
                    return;
                }

                document.getElementById('response-input').value = '';

                displayPopup(this.responseFoundMessage, {
                    autoCloseDelay: this.popupDelay,
                    onClose: () => {
                        this.addNewResponse(response.data);
                    }
                });
            })
        ;
    };

    /**
     * Allows to add new response in list.
     * 
     * @param object response
     */
    addNewResponse(response) {
        const responseElement = document.createElement('li');
        responseElement.dataset.response = '';
        responseElement.dataset.responseId = response.id;
        responseElement.dataset.positionX = response.positionX;
        responseElement.dataset.positionY = response.positionY;
        responseElement.dataset.width = response.width;
        responseElement.dataset.height = response.height;
        responseElement.innerHTML = response.title;

        const parentElement = document.getElementById('quiz-responses');
        parentElement.insertBefore(responseElement, parentElement.firstChild);

        responseElement.addEventListener('click', (event) => {
            this.responseLocator.displayResponseLocation(event.target.dataset);
        });

        if (this.quizResolver.allResponsesFound()) {
            this.quizResolver.handleQuizResolution();
        } else {
            this.responseLocator.displayResponseLocation(responseElement.dataset);
        }

        document.getElementById('quiz-response-count').innerHTML
            = document.querySelectorAll('#quiz-responses li[data-response]').length;
    };
}
