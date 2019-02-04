import PerfectScrollbar from 'perfect-scrollbar';
import QuizResolver from './quiz-resolver';
import ResponseHelper from './response-helper';
import ResponseLocator from './response-locator';
import ResponseResolver from './response-resolver';

/**
 * Allows to initialize quiz.
 *
 * @param int popupDelay
 * @param int locationDelay
 */
const initQuiz = (popupDelay, locationDelay) => {
    new PerfectScrollbar(document.getElementById('scroll-wrapper'));

    const quizInfo = document.getElementById('quiz-play').dataset;

    const quizResolver = new QuizResolver(
        +quizInfo.quizId,
        +quizInfo.responseCount,
        quizInfo.resolveUrl,
        popupDelay
    );

    const responseHelper = new ResponseHelper(
        +quizInfo.quizId,
        quizInfo.trickUrl,
        +quizInfo.pictureWidth,
        +quizInfo.pictureHeight,
        popupDelay
    );

    const responseLocator = new ResponseLocator(
        +quizInfo.pictureWidth,
        +quizInfo.pictureHeight,
        locationDelay,
        2
    );

    const responseResolver = new ResponseResolver(
        +quizInfo.quizId,
        quizInfo.responseUrl,
        responseLocator,
        quizResolver,
        popupDelay
    );

    initActions(responseHelper, responseLocator, responseResolver);
};

/**
 * @param ResponseHelper responseHelper
 * @param ResponseLocator responseLocator
 * @param ResponseResolver responseResolver
 */
const initActions = (responseHelper, responseLocator, responseResolver) => {
    document.getElementById('response-submit') && document.getElementById('response-submit').addEventListener(
        'click',
        (event) => {responseResolver.submitResponse(document.getElementById('response-input').value);}
    );

    document.getElementById('response-input') && document.getElementById('response-input').addEventListener(
        'keydown',
        (event) => {
            if ('Enter' === event.key || 13 === event.keyCode) {
                responseResolver.submitResponse(event.target.value);
            }
        }
    );

    [...document.querySelectorAll('li[data-response]')].forEach((element) => {
        element.addEventListener('click', (event) => {
            responseLocator.displayResponseLocation(event.target.dataset);
        });
    });

    document.getElementById('quiz-trick') && document.getElementById('quiz-trick').addEventListener(
        'click',
        (event) => {responseHelper.toggleTrickingMode();}
    );

    document.getElementById('picture-quiz').addEventListener('click', (event) => {
        responseHelper.triggerTrickEvent(event)
    });

    document.getElementById('response-input') && document.getElementById('response-input').focus();
};

document.getElementById('quiz-play') && initQuiz(1400, 2200);