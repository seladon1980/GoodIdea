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
        order: json.order,
        by: json.by,
        blogData: json
    };
}

function apiPath() {
    return "http://localhost:8000";
}

export function navigate(p, next) {
    return (dispatch, getState) => {
        dispatch(startFetchingCard());
        let page = parseInt(getState().page.postSlug);
        let order = getState().page.order;
        let by = getState().page.by;
        if(next) {
            page+=1;
        } else  {
            page-=1;
        }

        let url = apiPath() + "/blog/" + page + "/?by="+by+"&order="+order;
        return fetch(url)
            .then(response => response.json())
            .then(json => dispatch(finishFetchingCard(json)));
    };
}


export function destroy(event, id, data) {
    return (dispatch, getState) => {
        dispatch(startFetchingCard());

        let state = data;
        state.data = state.data.filter(function (candidate) {
            return candidate.id !== id;
        });

        let url = apiPath() + "/blog/post/"+id;
        return fetch(url, {
            method: 'DELETE',
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {'Content-Type': 'application/json',},
            body: JSON.stringify({'id': id}),
        }).then(response => response.json())
            .then(json => dispatch(finishFetchingCard(state)));

    };
}

export function completedClear(event, id, data) {
    return (dispatch, getState) => {
        dispatch(startFetchingCard());

        let page = parseInt(getState().page.postSlug);
        let order = getState().page.order;
        let by = getState().page.by;

        let url = apiPath() + "/blog/clear/";
        return fetch(url, {
            method: 'POST',
            headers: {'Content-Type': 'application/json',},
            body: JSON.stringify({
                'method': 'completedClear',
                'order': order,
                'by': by,
                'page': page
            }),
        }).then(response => response.json())
            .then(json => dispatch(finishFetchingCard(json)));

    };
}


export function order(page, by, order) {
    return (dispatch, getState) => {
        dispatch(startFetchingCard());
        let page = parseInt(getState().page.postSlug);

        order=order==='ASC'?'DESC':'ASC';

        let url = apiPath() + "/blog/" + page + "/?by="+by+"&order="+order;
        return fetch(url)
            .then(response => response.json())
            .then(json => dispatch(finishFetchingCard(json)));
    };
}