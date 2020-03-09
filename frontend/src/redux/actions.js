export const SET_COOKIE = "SET_COOKIE";
export const ADD_PROMISE = "ADD_PROMISE";
export const REMOVE_PROMISE = "REMOVE_PROMISE";
export const START_FETCHING_POST = "START_FETCHING_POST";
export const FINISH_FETCHING_POST = "FINISH_FETCHING_POST";

export function fetchCardIfNeeded() {
    return (dispatch, getState) => {
        let state = getState().page;
        if (state.blogData === undefined || state.blogData.slug !== state.postSlug) {
            return dispatch(fetchCard());
        }
    };
}

function fetchCard() {
    return (dispatch, getState) => {

        dispatch(startFetchingCard());
        let url = apiPath() + "/blog/" + getState().page.postSlug;

        return fetch(url)
            .then(response => response.json())
            .then(json => dispatch(finishFetchingCard(json)));
    };

}

function startFetchingCard() {
    return {
        type: START_FETCHING_POST
    };
}

function finishFetchingCard(json) {
    return {
        type: FINISH_FETCHING_POST,
        postSlug: json.page,
        blogData: json
    };
}

function apiPath() {
    return "http://localhost:8000";
}

export function navigate(page, next) {


    return (dispatch, getState) => {

        dispatch(startFetchingCard());

        let page = parseInt(getState().page.postSlug);
        if(next) {
            page+=1;
        } else  {
            page-=1;
        }

        let url = apiPath() + "/blog/" + page;

        return fetch(url)
            .then(response => response.json())
            .then(json => dispatch(finishFetchingCard(json)));
    };
}