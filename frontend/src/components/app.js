import React, {Component} from 'react'
import {connect} from "react-redux";

import {navigate, order} from "../redux/actions";

import PostPage from "./postPage"

class App extends Component {

    componentDidMount() {
        history.replaceState({
            pathname: location.pathname,
            href: location.href
        }, "");

        window.addEventListener("popstate", event => this.navigate(event));
    }

    navigate(event, type) {
        this.props.dispatch(navigate(event, type));
    }

    order(page, by, sort) {
        this.props.dispatch(order(page, by, sort));
    }

    render() {
        const {pageType, order} = this.props;
        return (
            <div>
                <header className="header">
                    <h1>todos</h1>
                </header>
                {pageType === "blog" && <PostPage />}
                <footer className="footer">
                    <ul className="filters">
                        <span className="todo-count"
                              onClick={event => this.order(event, 'like', order)}>
						    <strong>По лайкам</strong>
					    </span>
                        <li>
                            <a onClick={event => this.navigate(event, false)}>
                                Назад
                            </a>
                        </li>
                        {' '}
                        <li>
                            <a onClick={event => this.navigate(event, true)}>
                                Вперед
                            </a>
                        </li>
                    </ul>
                    <button className="clear-completed">
                        Clear completed
                    </button>
                </footer>
            </div>
        );
    }
}

function mapStateToProps(state) {
    const {page} = state;
    const {type} = page;
    return {
        pageType: type,
        order: page.order
    };
}

export default connect(mapStateToProps)(App);