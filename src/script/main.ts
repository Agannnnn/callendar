document.addEventListener("DOMContentLoaded", () => {
  const formAddEvent = document.querySelector("#add-event") as HTMLFormElement;
  const formEditEvent = document.querySelector(
    "#edit-event"
  ) as HTMLFormElement;
  const editEventBtn = document.querySelectorAll(
    ".edit-event-btn"
  ) as NodeListOf<HTMLButtonElement>;
  const hapusEventBtn = document.querySelectorAll(
    ".delete-event-btn"
  ) as NodeListOf<HTMLButtonElement>;
  const eventsCard = document.querySelectorAll(
    ".day"
  ) as NodeListOf<HTMLDivElement>;

  formAddEvent.addEventListener("submit", (e) => {
    e.preventDefault();

    const name = (formAddEvent.elements[0] as HTMLInputElement).value;
    const description = (formAddEvent.elements[3] as HTMLInputElement).value;
    const dateFrom = (formAddEvent.elements[1] as HTMLInputElement).value;
    const dateTo = (formAddEvent.elements[2] as HTMLInputElement).value;

    if (name.match(/^.+$/)) {
      fetch(new URL("api/event.php", window.location.toString()), {
        method: "POST",
        credentials: "include",
        headers: {
          Accept: "application/json",
          "Content-type": "application/json",
        },
        body: JSON.stringify({
          name,
          description,
          dateFrom,
          dateTo,
          method: "POST",
        }),
      })
        .then((res) => res.json())
        .then((res) => {
          if ((res.code = 200)) {
            alert(res.message);
            window.location.reload();
            return;
          }
          alert(res.message);
        })
        .catch(() => alert("Failed to insert new event"));
    } else {
      alert("Please fill all the inputs");
    }
  });

  formEditEvent.addEventListener("submit", (e) => {
    e.preventDefault();

    const eId = (formEditEvent.elements[0] as HTMLInputElement).value;
    const name = (formEditEvent.elements[1] as HTMLInputElement).value;
    const description = (formEditEvent.elements[4] as HTMLInputElement).value;
    const dateFrom = (formEditEvent.elements[2] as HTMLInputElement).value;
    const dateTo = (formEditEvent.elements[3] as HTMLInputElement).value;

    if (name.match(/^.+$/)) {
      fetch(new URL("api/event.php", window.location.toString()), {
        method: "POST",
        credentials: "include",
        headers: {
          Accept: "application/json",
          "Content-type": "application/json",
        },
        body: JSON.stringify({
          eId,
          name,
          description,
          dateFrom,
          dateTo,
          method: "PATCH",
        }),
      })
        .then((res) => res.json())
        .then((res) => {
          if ((res.code = 200)) {
            alert(res.message);
            window.location.reload();
            return;
          }
          alert(res.message);
        })
        .catch(() => alert("Failed to update event"));
    } else {
      alert("Please fill all the inputs");
    }
  });

  eventsCard.forEach((card) => {
    card.addEventListener("click", () => {
      const cardDate = `${card.dataset.year}-${
        parseInt(card.dataset.month as string) < 10
          ? `0${card.dataset.month}`
          : card.dataset.month
      }-${
        parseInt(card.dataset.day as string) < 10
          ? `0${card.dataset.day}`
          : card.dataset.day
      }`;

      (formAddEvent.elements[1] as HTMLInputElement).value = "";
      (formAddEvent.elements[2] as HTMLInputElement).value = "";
      (formAddEvent.elements[0] as HTMLInputElement).focus();

      document.querySelector("#event-list-date")!.innerHTML =
        card.dataset.day ?? "";

      const cardEvents = (
        JSON.parse(card.dataset.events ?? "[]") as {
          e_id: string;
          name: string;
        }[]
      ).map((event) => {
        return `<li>
            <span>
              ${event.name}
            </span>
            <div class="buttons">
              <button class="edit-event-btn" data-e-id="${event.e_id}" onclick="editEventHandler('${event.e_id}')">Edit</button>
              <button class="delete-event-btn" data-e-id="${event.e_id}" onclick="deleteEventHandler('${event.e_id}')">Remove</button>
            </div>
          </li>`;
      });

      document.querySelector(".events-list .events")!.innerHTML =
        cardEvents.join("");
    });
  });
});

const editEventHandler = (eventId: string) => {
  fetch(new URL(`api/event.php?eId=${eventId}`, window.location.toString()), {
    method: "GET",
    credentials: "include",
    headers: {
      Accept: "application/json",
      "Content-type": "application/json",
    },
  })
    .then((res) => res.json())
    .then((eventData) => {
      const formEditEvent = document.querySelector(
        "#edit-event"
      ) as HTMLFormElement;
      (
        formEditEvent.querySelector("#inputs") as HTMLDivElement
      ).classList.remove("hidden");

      const formEditInputs = formEditEvent.elements;

      (formEditInputs[0] as HTMLInputElement).value = eventId;
      (formEditInputs[1] as HTMLInputElement).value = eventData.name;
      (formEditInputs[4] as HTMLInputElement).value = `${eventData.description}`
        .split(/\s/)
        .map((kata) => {
          kata = kata.replace(/[^\\r]\\n/g, "\r\n");
          kata = kata.replace(/\\\\/g, "\\");
          return kata;
        })
        .join(" ");
      (formEditInputs[2] as HTMLInputElement).value = eventData.start;
      (formEditInputs[3] as HTMLInputElement).value = eventData.end;
    });
};

const deleteEventHandler = (eventId: string) => {
  fetch(new URL("api/event.php", window.location.toString()), {
    credentials: "include",
    method: "POST",
    headers: {
      Accept: "application/json",
      "Content-type": "application/json",
    },
    body: JSON.stringify({ eId: eventId, method: "DELETE" }),
  })
    .then((res) => res.json())
    .then((res) => {
      if (res.code === 203) {
        alert(res.message);
        window.location.reload();
        return;
      }
      alert(res.message);
      return;
    })
    .catch(() => alert("Failed to remove event"));
};
