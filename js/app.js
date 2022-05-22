/**
 *
 * This Javascript is written by Jason Reed, except the gql function taken from https://catalins.tech/hashnode-api-how-to-display-your-blog-articles-on-your-portfolio-page
 *
 * Copyright 2017-2022.
 * This code may be used as is.
 *
 *
 *
 */

const GET_USER_ARTICLES = `
query GetUserArticles($page: Int!){
  user(username: "jrsofty") {
    publication {
      domain
      posts(page: $page) {
        title
        cuid
        brief
        slug
        isActive
      }
    }
  }
}`;

const maxPosts = 3;

async function gql(query, variables = {}) {
  const data = await fetch("https://api.hashnode.com/", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      query,
      variables,
    }),
  });

  return data.json();
}

async function devto() {
  const data = await fetch("https://dev.to/api/articles?username=jrsofty");
  return await data.json(); // dev.to requires an await to get the data. Not sure why though.
}

/**
 * Let's not repeat ourselves shall we?
 */
function buildCard(id, title, content, link) {
  let cardwrapper = document.createElement("div");
  cardwrapper.setAttribute(
    "class",
    "col-md-3 d-flex align-items-stretch cardwrapper"
  );

  let card = document.createElement("div");
  card.setAttribute("id", id);
  card.setAttribute("class", "card");

  let cardBody = document.createElement("div");
  cardBody.setAttribute("class", "card-body");

  let cardTitle = document.createElement("h3");
  cardTitle.setAttribute("class", "card-title");
  cardTitle.innerText = title;

  let cardText = document.createElement("p");
  cardText.setAttribute("class", "card-text");
  cardText.innerText = content;

  let moreButton = document.createElement("a");
  moreButton.setAttribute("class", "card-link");
  moreButton.setAttribute("target", "_blank");
  moreButton.innerText = "Read More ...";
  moreButton.href = link;

  cardBody.appendChild(cardTitle);
  cardBody.appendChild(cardText);
  cardBody.appendChild(moreButton);
  card.appendChild(cardBody);
  cardwrapper.appendChild(card);
  return cardwrapper;
}
