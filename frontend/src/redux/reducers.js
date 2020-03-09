import {
    FINISH_FETCHING_POST,
    START_FETCHING_POST,
} from "./actions";


export default function root(state = {}, action) {
    switch (action.type) {
        case START_FETCHING_POST:
            return {
                ...state,
                page: {
                    ...state.page,
                    blogData: {"data":[]},
                    isFetching: true
                }
            };
        case FINISH_FETCHING_POST:
            return {
                ...state,
                page: {
                    ...state.page,
                    isFetching: false,
                    blogData: action.blogData,
                    postSlug: action.blogData.page,
                    by: action.blogData.by,
                    order: action.blogData.order
                }
            };
    }
    return state;
}