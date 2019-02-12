import PerfectScrollbar from 'perfect-scrollbar';
import QuizResolver from './quiz-resolver';
import ResponseHelper from './response-helper';
import ResponseLocator from './response-locator';
import ResponseResolver from './response-resolver';
import { bindSwitchButton } from '../../component/switch';

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

    document.getElementById('response-input') && document.getElementById('response-input').focus();

    document.getElementById('switch-mode-button')
        && bindSwitchButton(document.getElementById('switch-mode-button'), (item) => {responseHelper.toggleHelperMode(item.dataset.mode);});

    [...document.querySelectorAll('li[data-response]')].forEach((element) => {
        element.addEventListener(type, (event) => {
            responseLocator.displayResponseLocation(event.target.dataset);
        });
    });

    document.getElementById('picture-quiz').addEventListener('click', (event) => {
        responseHelper.triggerTrickEvent(event, false)
    });
    document.getElementById('picture-quiz').addEventListener('touchstart', (event) => {
        responseHelper.triggerTrickEvent(event, true)
    });
};

document.getElementById('quiz-play') && initQuiz(1400, 2200);
