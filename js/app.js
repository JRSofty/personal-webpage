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
  return await data.json();
}

function buildCard(id, title, content, link) {
  let cardwrapper = document.createElement("div");
  cardwrapper.setAttribute("class", "col-md-3 d-flex align-items-stretch");

  let card = document.createElement("div");
  card.setAttribute("id", id);

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
